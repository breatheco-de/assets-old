[<- Back to the Main Readme](../docs/README.md)

# BreatheCode API's

This directory contains several APIs needed to run the breathecode platform:

1. [/Credentials](./credentials/README.md): OAuth implementation on the BC Platform
2. [/Quizes](./quiz/README.md): Used by the breathecode platform quizes.
2. [/Lessons](./lesson/README.md): All the lessons included on breathecode.
3. [/Sounds](./sound/README.md): Sounds for games and tutorials.
2. [/Events](./event/README.md): For the academy events and workshops
4. [/NPS](./nps/README.md): API implementation for Net Promoter Score
5. [/Kill-The-Bug](./kill-the-bug/README.md): Great game to play with audiences interested in learning to code.
6. [/VTutorials](./vtutorial/README.md): Use by the breathecode platform to enhance the video tutorials (captions, instructions, etc).
7. [/Syllabus](./syllabus/README.md): All the syllabus available on the BC Platform
8. [/Replit](./replit/README.md): All the replits available on the BC Platform
9. [/Projects](./project/README.md): All the lessons included on projects.breatheco.de
10. [/Img](./img/README.md): Database of images for tutorials, marketing, etc.
11. [/Fake](./fake/README.md): A series of endpoints exposed for dummy calls on the breathecode tutorials and exercises
12. [/Hooks](./hoos/README.md): Hooks used by 3rd party services like ActiveCampaign, Zapier, etc.

## Usage

All APIs are created using this package: [SlimAPI](https://github.com/alesanchezr/slim-api-wrapper) that is based on [Slim PHP Framwork](https://www.slimframework.com/) to create each of the APIs, and it also 
contains other class helpers to interface with SQLite, JSON Files, Amazon Email Service, etc.

Here is a small manual on how to create a new api for breathecode
https://github.com/breatheco-de/main-documentation/blob/master/Contribution_Manuals/coder/CREATE_NEW_ASSETS_API.md
