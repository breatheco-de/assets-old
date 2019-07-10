[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Sound's used on BreatheCode Exercises


GET: /sounds/all
```json
    {
        data/fx.json: [],
        data/songs.json: []
    }
```
GET: /sounds/fx
```json
    [
        {
            id: 1,
            category: "game",
            name: "Game Over",
            url: "data/mario/fx_gameover.wav"
        },
        {
            id: 2,
            category: "game",
            name: "Jump Super",
            url: "data/mario/fx_jump_super.wav"
        }
    ]
```
GET: /sounds/songs
```json
    [
    	{ "id":1, "category":"game", "name":"Mario Castle", "url":"files/mario/songs/castle.mp3" },
    	{ "id":2, "category":"game", "name":"Mario Star", "url":"files/mario/songs/hurry-starman.mp3"},
    	{ "id":3, "category":"game", "name":"Mario Overworld", "url":"files/mario/songs/overworld.mp3"}
    ]
```

## Here is an example of a song hosted on our servers:
[https://assets.breatheco.de/apis/sound/files/mario/songs/castle.mp3](https://assets.breatheco.de/apis/sound/files/mario/songs/castle.mp3)

You can see that all songs URL's start with `https://assets.breatheco.de/apis/sound/` and then are concatenated with the song path like this:

```js
const songURL = "https://assets.breatheco.de/apis/sound/" + "files/mario/songs/castle.mp3";
```
```
