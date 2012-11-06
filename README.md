# Convert emoji into HTML images for phpBB3

This is a work in progress MOD for phpBB3 that converts iOS emojis into HTML images. This MOD is based on dpaanlka’s work posted on [phpBB forums](https://www.phpbb.com/community/viewtopic.php?f=70&t=2111155).

Emoji images are mostly from [github/gemoji](https://github.com/github/gemoji). Any_SoftbankSMS.txt is from [Apple](http://opensource.apple.com/source/ICU/ICU-461.13/icuSources/data/translit/Any_SoftbankSMS.txt?txt).

## Installation

### 1. Update Database Schema to Support 4-Byte UTF-8

Instructions here are for MySQL only. To store 4-byte utf-8, MySQL version 5.5 and above is required. Change collation of `phpbb_posts.post_text` to `utf8mb4_unicode_ci`. Repeat this step for any other places, such as `phpbb_forums.forum_desc`, that may possibly store emoji.

Open `phpBB/includes/db/mysqli.php`, change:

```php
			@mysqli_query($this->db_connect_id, "SET NAMES 'utf8'");
```

to:

```php
			@mysqli_query($this->db_connect_id, "SET NAMES 'utf8mb4'");
```

### 2. Copy Files

* Copy `images/emoji` into `phpBB/images`
* Copy `styles/style/template/emoji.js` into `phpBB/styles/<your-style>/template`
* Copy `styles/style/template/jquery-1.8.2.min.js` into `phpBB/styles/<your-style>/template`  
  **Skip this step if your style already comes with jQuery!**

### 3. Add JavaScript Tags

Open `phpBB/styles/<your-style>/template/overall_header.html`, before

```html
<link href="{T_THEME_PATH}/print.css" rel="stylesheet" type="text/css" media="print" title="printonly" />
```

add

```html
<script type="text/javascript" src="{T_SUPER_TEMPLATE_PATH}/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="{T_SUPER_TEMPLATE_PATH}/emoji.js"></script>
```

__Do not add the jQuery line if your style already comes with jQuery!__

### 4. Update Stylesheet

Emoji images come at 64px x 64px, which would be too big. Open `phpBB/styles/<your-style>/theme/common.css` and add at the bottom:

```css
img.emoji {
	width: 1.5em;
	height: 1.5em;
}
```

### 5. Clear Cache

Go to phpBB admin and click clear cache.

## License

All images and Any_SoftbankSMS.txt:

Copyright (c) 2012 Apple Inc. All rights reserved.

The rest:

```
  Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
```

