[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Reminders API

Setup and mantain reminders for the academy operations

#### Get all reminders
```
GET: /reminders/types
```

#### Get expired reminders (that should execute)
```
GET: /reminders/expired
```

#### Execute all reminders
```
GET: /reminders/execute/all
```

#### Execute next reminder by priority
```
GET: /reminders/execute/next
```

#### Execute particular reminder even if its not due
```
GET: /reminders/execute/single/{name}
```

Test all the reminders configuration by running:
```php
php apis/reminders/test.php 
```

Test a particular reminder calling