[<- Back to the APIs Readme](../docs/README.md) or [APPs Readme](../README.md)

# Oauth Application

Login, SignUp and Remind password for the BC Platform

## Why the login is separated from the rest of the developments?

- Because you don't want to implement a login on each web development.
- Because doing it the good old way (monolitic approach) ensures the browser does not get confused.
- Because the industry standard syas you should refresh the page.
- Because a Remind password comes from the email, we need a separated link.
- Because we implement Oauth and we need to ask the user for the scopes.

## Who is using it?

1. student.breatheco.de (in progress)
2. admin.breatheco.de (pending)
3. teacher.breatheco.de (pending)


## TODO

1. Change the name of the APP to oauth (beware that you could break many login forms throguht the entire platform)
2. Implement it on all the applications.