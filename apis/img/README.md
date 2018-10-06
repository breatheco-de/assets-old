[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# ![alt text](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,128) Image API - Breathe Code
HOST:</span> https://assets.breatheco.de/apis/img

## Get list of all images in JSON ([preview](/apis/img/images.php))

```
[GET] /apis/img/images.php
Params:
- cat: funny (string)
- tags: tag1,tag2,...tag (string of terms separated by comma)
```

## Get only one random image
```
    [GET] /apis/img/images.php?random
    You can combine it with any other parameters.
```

## Get result as blob image
```
    [GET] /api/img/images.php?blob
    You can combine it with any other parameters.
```
### Categories
```
[GET] /apis/img/images.php?getcategories

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
[GET] /apis/img/images.php?gettags

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

Get list off all images
[/apis/img/images.php](/apis/img/images.php)
Get one image in a particular size
[/apis/img/images.php?blob&tags=bobdylan&size=200](/apis/img/images.php?blob&tags=bobdylan&size=200)
Get the breathecode logo in 3 sizes
[/apis/img/images.php?blob&random&cat=icon&tags=breathecode,128](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,128)
[/apis/img/images.php?blob&random&cat=icon&tags=breathecode,16](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,128)
[/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,128)
Get 4geeks academy icon
[/apis/img/images.php?blob&random&cat=icon&tags=4geeks](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,128)
Get 4geeks academy logo in black and small
[http://assets.breatheco.de/apis/img/images.php?blob&random&cat=logo&tags=4geeks,black,small](http://assets.breatheco.de/apis/img/images.php?blob&random&cat=logo&tags=4geeks,black,small)
Get random funny images
[http://assets.breatheco.de/apis/img/images.php?blob&random&cat=funny](http://assets.breatheco.de/apis/img/images.php?blob&random&cat=funny)