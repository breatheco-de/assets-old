
# ![alt text](https://assets.breatheco.de/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32) BreatheCode Exercise Assets
Resources for Teachers and Students using the [BreatheCode Platform](https://breatheco.de).
## Available Assets API's

[Images](apis/img/), [Sounds](apis/sound/), [Quizzes](apis/quiz/), [Lessons](apis/lesson/), [Videos](apis/video/), [Replits](apis/replit/), [Events](apis/event/)

#### Important Note: 
Some API methods are private and they will require the use of and access token in the URL query string like this:
> METHOD: /path/to/resource/?access_token={your_access_token}

The access token can be generated with this endpoint:
```
POST: /apis/events/token/generate
    
    PARAMS:
    - client_id (string): your client id
    - client_pass (string): your client password
```
If you don't have a client_id or client_pass send us an email to request it.

## Other Sample API's for Projects

- [Fake Sample API](../apis/fake/)
- [TicTacToe API](../apis/fake/tictactoe/)
- [TODO's API](../apis/fake/todos/)
- [Meetup API](../apis/fake/meetup/)
- [Contact Management API](../apis/fake/contact/)

## Very Useful tools

- [Screen Viewer](/apps/screen/): Show screens on events or the office.
- [Quiz Maker](/apps/quiz-maker/): Create or edit BC Quizzes easy.
- [Syllabus Maker](/apps/syllabus-maker/): Create or edit academy syllabus.
- [Replit Editor](/apps/replit-maker/): Create or edit academy syllabus.
- [Video Tutorials](/apps/video/): Shoot POST and GET requests and it will show you the content.
- [HTTP Sniffer](live-demos/php/forms/): Shoot POST and GET requests and it will show you the content.
- [Markdown Parser](apps/markdown-parser/): Pass a markdown file path to the url and it will render it like github
- [Regex Tester](live-demos/js/regex-tester/): Test Regular expressions
- [MySQL Tester](live-demos/sql/mysql-tester/): Test your MySQL Knowledge
- [Aspect Ratio](live-demos/css/aspect-ratio/)
- [Bootstrap Theme Tester](live-demos/css/bootstrap/): Test Different bootstap themes
- [The Box Model](live-demos/css/box-model/): Test how the box model works
- [Perspective](live-demos/css/perspective/): Test the perspective CSS rule.
- [Translate](live-demos/css/translate/): Test the translate CSS rule.
- [Event Information](live-demos/js/event-information/): Test all the js events
- [Event Triggering](live-demos/js/event-triggering/): Test how events get triggered.
