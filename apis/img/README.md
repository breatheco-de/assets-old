# ![alt text](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,128) Image API - Breathe Code
HOST:</span> https://assets.breatheco.de/apis/img

## Get list of images in JSON

```
[GET] /images.php
Params:
- cat: funny (string)
- tags: tag1,tag2,...tag (string of terms separated by comma)
```

## Get only one random image
```
    [GET] /images.php?random
    You can combine it with any other parameters.
```

## Get result as blob image
```
    [GET] /images.php?blob
    You can combine it with any other parameters.
```
### Categories
```
[GET] /images.php?getcategories

//Example
[
  "bg",
  "class-diagrams",
  "funny",
  "icon",
  "logo",
  "meme",
  "other",
  "replit",
  "scientific"
]
```
## Tags
```
[GET] /images.php?gettags

//Example
[
  "background-vertical",
  "small-mosaic",
  "kids",
  "rigoberto",
  "scared-baby",
  "jquery-dom-en",
  "ebbinghaus"
]
```

## Examples

Get the breathecode logo in 3 sizes
```
GET: /img/images.php?blob&random&cat=icon&tags=breathecode,128
GET: /img/images.php?blob&random&cat=icon&tags=breathecode,16
GET: /img/images.php?blob&random&cat=icon&tags=breathecode,32
```