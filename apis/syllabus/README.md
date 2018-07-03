[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Syllabus API

#### 1) Get a course syllabus
```
GET: syllabus/{slug}
```
## Examples

[Get pre-work syllabus](/apis/syllabus/coding-introduction)

#### 2) Get all syllabus

```js
GET: /syllabus/all

Response:
[
    {
        "slug": "blockchain",
        "title": "Blockchain",
        "weeks": 1,
        "days": 3,
        "technologies": []
    }
    ...
]
```

#### 3) Update a course syllabus
```
POST: syllabus/{slug}
```