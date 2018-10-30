# ![alt text](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32) Fake Contact-List API

Use this API to test your skills doing AJAX requests with the [contact-list](https://projects.breatheco.de/d/contact-list#readme) ajax exercise on the BreatheCode Platform.

[Right-click here to download the POSTMan Collection](https://assets.breatheco.de/apis/fake/contact/collection.json).

#### 1) Get All contacts from an Agenda
```
GET: /apis/fake/contact/agenda/{agenda_slug}
```

#### 2) Get One Particular Contact
```
GET: /apis/fake/contact/{contact_id}
```

#### 3) Delete One Particular Contact
```
DELETE: /apis/fake/contact/{contact_id}
```

#### 4) Create one contact
```
PUT: /apis/fake/contact/

Request (application/json)

    body:
    {
        "full_name": "Dave Bradley",
        "email": "dave@gmail.com",
        "agenda_slug": "my_super_agenda",
        "address":"47568 NW 34ST, 33434 FL, USA",
        "phone":"7864445566"
    }
```

#### 5) Update one contact
```
POST: /apis/fake/contact/{contact_id}

Request (application/json)

    body:
    {
        "full_name": "Dave Bradley",
        "email": "dave@gmail.com",
        "agenda_slug": "my_super_agenda",
        "address":"47568 NW 34ST, 33434 FL, USA",
        "phone":"7864445566"
    }
```
