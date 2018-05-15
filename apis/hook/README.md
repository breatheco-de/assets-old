[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# BreatheCode Hooks API

1. Get student referral_code
```sh
GET: /referral_code&email=<some@email.com>
```

2. Update student referral_code on active campaign
```sh
POST: /referral_code
Content-type: x-www-form-urlencoded
Params:
    email (string)
```

3. Update student breathecode info on active campaign
```sh
POST: /update_bc_info_on_ac
Content-type: x-www-form-urlencoded
Params:
    email (string)
```

4. Initialize contact info on active campaign
```sh
POST: /initialize
Content-type: x-www-form-urlencoded
Params:
    email (string)
```

5. Sync BC user info with Active Campaign
```sh
POST: /sync/contact
Content-type: x-www-form-urlencoded
Params:
    email (string)
```

5. Invite BC User/Student to slack
```sh
POST: /slack/invite
Content-type: x-www-form-urlencoded
Params:
    email (string)
```