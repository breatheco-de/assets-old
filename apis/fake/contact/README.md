# ![alt text](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32) Fake Contact-List API

Use this API to test your skills doing AJAX requests with the [contact-list](https://projects.breatheco.de/d/contact-list#readme) ajax exercise on the BreatheCode Platform.

[Right-click here to download the POSTMan Collection](https://assets.breatheco.de/apis/fake/contact/collection.json).

#### 1) Get All available agendas right now
```
GET: /apis/fake/contact/agenda
```

#### 2) Create an agenda

To create an agenda all you have to do is create a contact with a unused agenda_slug and the agenda will automatically be created.

#### 3) Get All contacts from an Agenda
```
GET: /apis/fake/contact/agenda/{agenda_slug}
```

#### 4) Get One Particular Contact
```
GET: /apis/fake/contact/{contact_id}
```

#### 5) Delete One Particular Contact
```
DELETE: /apis/fake/contact/{contact_id}
```

#### 6) Delete All Contacts from an Agenda
```
DELETE: /apis/fake/contact/agenda/{agenda_slug}
```

#### 7) Create one contact
```
POST: /apis/fake/contact/

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

#### 8) Update one contact
```
PUT: /apis/fake/contact/{contact_id}

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
