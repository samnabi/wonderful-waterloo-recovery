<?php
// Scrape posts/threads, and convert it to JSON

// include Simple HTML DOM library
include_once('../vendor/simple_html_dom.php');

// Initialize the forums, threads, and posts arrays. These are the main structural divisions.
$forums = array();
$threads = array();
$posts = array();

// Keeping track of total number of posts
$numposts = 0;

// loop through subset_posts directory
if ($handle = opendir('../raw_data/subset_posts/')) {
    while (false !== ($filename = readdir($handle))) {
        if ($filename != "." && $filename != "..") {
        	$html = file_get_html('../raw_data/subset_posts/'.$filename);

            // Loop through each post
            foreach($html->find('.postcontainer') as $post){

            	// Set post id
            	$postID = substr($post->id,5);

            	// Extract extra user info
            	$userinfo = explode('|', $post->find('.userinfo_extra',0)->plaintext);
            	if(count($userinfo) == 2) {
            		array_unshift($userinfo,'');
            	}

            	// Extract last edited info
            	$lastedited = explode('; ', trim($post->find('.lastedited',0)->plaintext));

            	// Build the post array
            	$posts[$postID] = array(
            		'user' => array(
            			'name' => trim($post->find('.username strong',0)->plaintext),
            			'avatar' => $post->find('.postuseravatarlink img',0)->src,
            			'title' => trim($post->find('.usertitle',0)->plaintext),
            			'extra_info' => array(
            				'from' => substr(trim($userinfo[0]),5),
            				'member_since' => substr(trim($userinfo[1]),13),
            				'num_posts' => str_replace(',','',substr(trim($userinfo[2]),0,-6))
            			)
            		),
            		'content' => trim($post->find('.postcontent',0)->innertext),
            		'signature' => trim($post->find('.signaturecontainer',0)->innertext),
            		'date' => str_replace('&nbsp;',' ',trim($post->find('.postdate',0)->plaintext)),
            		'last_edited' => array(
            			'timestamp' => str_replace(' at ',' ',substr($lastedited[1],0,-2)),
            			'user' => substr($lastedited[0],15)
            		),
            		'num_in_thread' => substr($post->find('.postcounter',0)->plaintext,1)
            	);
            }

            // Set thread id
            preg_match('/&amp;t=(\d+)/', $html->find('.thread_controls',0)->innertext, $m);
            $threadID = $m[1];

            // Build parent forums list from breadcrumb menu
            $forums = array();
            $breadcrumbs = $html->find('.breadcrumb > ul > li');
            $breadcrumbs = array_slice($breadcrumbs, count($breadcrumbs)*0.5);
            array_pop($breadcrumbs); // Remove last item
            array_shift($breadcrumbs); // Remove first two items
            array_shift($breadcrumbs);
            foreach ($breadcrumbs as $forum) {
            	preg_match('/forumdisplay.php\/(\d+)/', $forum->find('a',0)->href, $m);
            	$forums[] = array(
            		'forum_id' => $m[1],
            		'forum_name' => trim($forum->find('a',0)->plaintext)
            	);
            }

            // Check if thread exists
            if(!array_key_exists($threadID, $threads)){
            	// If it doesn't exist, build the thread array
	            $threads[$threadID] = array(
	            	'parent_forums' => $forums,
	            	'title' => trim($html->find('title',0)->plaintext),
	            	'description' => $html->find('meta[name="description"]',0)->content,
	            	'keywords' => explode(', ',$html->find('meta[name="keywords"]',0)->content),
	            	'canonical_url' => $html->find('link[rel="canonical"]')->href,
	            	'num_posts' => trim(substr($html->find('.postpagestats',0)->plaintext,strpos($html->find('.postpagestats',0)->plaintext, 'of ')+3)),
	            	'posts' => $posts
	            );
            } else {
            	// If it exists, just add the posts to the thread.
            	foreach ($posts as $postID => $post) {
            		$threads[$threadID]['posts'][$postID] = $post;
            	}
            }

            // Status message, counter, and reset
            echo 'Added '.count($posts).' posts to thread '.$threadID."\n";
            $numposts += count($posts);
            $posts = array();
        }
    }
    closedir($handle);
}

// Convert $threads to JSON
$threadsJSON = json_encode($threads);

// Write JSON to file
date_default_timezone_set('UTC');
$JSONfilename = '../json/threads_'.date('U').'.json';
$fh = fopen($JSONfilename, 'w') or die("can't open file");
fwrite($fh, $threadsJSON);
fclose($fh);

// Status message
echo 'Success! Wrote '.count($threads).' threads containing '.$numposts.' posts'."\n";

?>
