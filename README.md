WonderfulWaterloo.com data recovery
===========================

WonderfulWaterloo.com, a thriving, vibrant community for urban issues in Waterloo Region, has been taken offline. It contained a huge amount of discussion and context that offers important historical context during a period of transformational change for the Region of Waterloo. It's a shame that it disappeared so abruptly. But archived copies of its content are scattered around the web, and hopefully we can rebuild some of what was lost.

So far, 5955 individual posts across 126 threads have been recovered from the forums, along with 471 images.

Contents of this repo
=========================

- **raw_data**
	- **warrick_dump** — the unadulterated recovered content. Keep these files untouched so they can serve as a messy blob of source material.
	- **subset_posts** — a subset of the *warrick_dump* files that only includes HTML files of forum posts
	- **subset_images** — a subset of the *warrick_dump* files that only includes images (minus GUI images)
- **scraper** — a script that turns the content of *subset_posts* into a nicely formatted single JSON file
- **json** — contains the output from the *scraper* script

How can I help recover archived material?
===============

**Upload pages/images from your local cache**. If you visited WonderfulWaterloo.com recently before it was taken offline, you may have local copies of those pages saved in your browser's cache. Use one of the methods below to save *anything you can* related to `wonderfulwaterloo.com`. Even if it seems irrelevant, don't delete it.

- Chrome - use this cache viewing tool: http://www.sensefulsolutions.com/2012/01/viewing-chrome-cache-easy-way.html
- Firefox - use this extension: https://addons.mozilla.org/en-US/firefox/addon/cacheviewer/
- Internet Explorer - go to `Tools` > `Internet Options` > `General` tab > `Temporary Internet Files` > `Settings` button > `View Files` button
- Safari - use this app: http://echoone.com/filejuicer/

> Once you've located your local cache of WonderfulWaterloo pages/assets, please submit a pull request so I can add them here. Or, if you're not familiar with git, get a hold of me at sam@samnabi.com.

**Use Warrick to scrape various web archives**. [Warrick](https://code.google.com/p/warrick/wiki/About_Warrick) is a command-line Perl utility for recovering websites from your local cache, archive.org, Google cache, Bing cache, et al. I have already used this tool with some success, but multiple attempts by different machines may deliver more results.

**Find externally-linked photos that people linked to in their posts**. Some users may have hotlinked photos from photobucket, flickr, or elsewhere on the web. These could still be around. You could scrape the JSON files for references to external photos, and save them to the data dump.

**Fix corrupted files**. There appear to be a lot of corrupted files in the `raw_data` folder. I'm no expert in fixing these kinds of issues, but there could be some content worth recovering there.

Please submit a pull request for any data you manage to recover. No need to filter anything out, we want as complete a record as possible even if some content seems irrelevant.