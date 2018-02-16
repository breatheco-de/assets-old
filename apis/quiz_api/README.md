# The Quiz API

HOST: https://assets.breatheco.de/apis/quiz_api/

## 1) Get list of all quizzes JSON
```
  [GET] /quizzes
  Params: None
  
    [
        {
            "info": {
                "name": "Test Your Knowledge!!",
                "main": "Think you're smart enough to be on Jeopardy? Find out!",
                "results": "Learn More: Etiam scelerisque, nunc ac egestas t volutpat. Maurid sit amet purus.",
                "badges": [
                    { "slug": "css_master", "points": 23 },
                    { "slug": "html_master", "points": 45 }
                ],
                "slug": "events",
                "category": "javascript"
            }
        }
    ]
```

## 2) Get only one quiz

```
  [GET] /quiz/<slug>
  
  The slug is a unique string id for each quiz in the platform for example "css", "html", etc.
  
    [
        {
            "info": {
                "name": "Test Your Knowledge!!",
                "main": "Think you're smart enough to be on Jeopardy? Find out!",
                "results": "Learn More: Etiam scelerisque, nunc ac egestas t volutpat. Maurid sit amet purus.",
                "badges": [
                    { "slug": "css_master", "points": 23 },
                    { "slug": "html_master", "points": 45 }
                ],
                "slug": "events",
                "category": "javascript"
            },
            "questions": [
                {
                    "q": "Which of the following is the correct event handler to detect a mouse click on a link?",
                    "a": [
                        { "option": "onMouseUp", "correct": false },
                        { "option": "onLink", "correct": false },
                        { "option": "onClick", "correct": true }
                    ]
                },
                {
                    "q": "Which of the following is the correct event handler to detect a mouse click on a link?",
                    "a": [
                        { "option": "onMouseUp", "correct": false },
                        { "option": "onLink", "correct": false },
                        { "option": "onClick", "correct": true }
                    ],
                    "select_any": true
                },
                ...
            ]
        },
        ...
    ]
```

## Rewrite URLS

Here is an example of a .htaccess file that has worked on production environments:

```sh
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /apis/quiz_api/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [L]
</IfModule>
```