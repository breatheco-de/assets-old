[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Syllabus API

#### Get a course syllabus
```
GET: syllabus/{slug}
```
## Examples

[Get pre-work syllabus](/apis/syllabus/web-intro)

# TODO:

1) We need a service to get al list of al the possible syllabus that I can retrieve

```js
GET: /apis/syllabus

Response:
[
    { slug: 'full-stack', title: 'Full Stack Web Development' },
    { slug: 'web-development', title: 'Web Development' }
]
```
