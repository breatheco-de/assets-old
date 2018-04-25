# Net Promoter Score

Simple App to get feedback from students

## Usage 

There are two main URLS:
```md
URL: /apps/nps/survey/<user_id>
To render a survey for a particular user

URL: /apps/nps/results
To review the results
```
## Installation

1. Set a .env file
2. Build with NPM

### .env example
```js
// .env

BASENAME=
DEBUG=true

//The wordpress host
CLIENT_ID=alesanchezr
CLIENT_SECRET=d04f78ef196471d5a954fe71aab4fe63bd95a8a4
BC_TOKEN=d69eae97e7f874c6cdf46de524178e8ca5f1583e
BC_HOST=https://talenttree-alesanchezr.c9users.io/

ASSETS_TOKEN=d69eae97e7f874c6cdf46de524178e8ca5f1583e
ASSETS_HOST=http://assets-alesanchezr.c9users.io/
```