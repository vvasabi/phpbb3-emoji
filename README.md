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
* Copy `includes/covert_emoji.php` into `phpBB/includes`

### 3. Add Function Call

Open `phpBB/includes/functions_content.php`, change:

```php
function smiley_text($text, $force_option = false)
{
	global $config, $user, $phpbb_root_path;
```

to

```php
function smiley_text($text, $force_option = false)
{
	global $config, $user, $phpbb_root_path;

	include_once dirname(__FILE__) . '/convert_emoji.php';
	$text = convert_emoji($text);
```

### 4. Update Stylesheet

Emoji images come at 64px x 64px, which would be too big. Open your theme’s `common.css`, which locates at `phpBB/styles/<theme>/theme/common.css`, and add at the bottom:

```css
img.emoji {
  width: 16px;
  height: 16px;
}
```

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

