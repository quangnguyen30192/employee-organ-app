## Setting up project on your machine

After you get the copy of this project and get it up running on your local machine by Laravel Homestead or XAMPP,...  

Run below scripts then it will work on your machine.

```bash
composer install
chmod -R o+rw storage
composer run post-root-package-install
composer run post-create-project-cmd

npm install
npm run dev
```

## References
In the project, we're using below existing source to support faster development
1. Jsonlint (https://github.com/zaach/jsonlint) - to check key duplication in json file
2. Start Bootstrap - Simple Sidebar (https://startbootstrap.com/template-overviews/simple-sidebar) - for faster bootstrap front-end
3. OrgChart (from https://github.com/dabeng/OrgChart) - show the employee hierarchy tree in organization chart view.

## Enjoy with the project

### API Testing
We have JSON API for testing (sending as POST request): `/employeeJsonApi`, you could use any REST client tool (e.g Postman) 
with the json below and send as POST request to `/employeeJsonApi`
 
```json
{ 
    "Pete": "Nick", 
    "Barbara": "Nick", 
    "Nick": "Sophie", 
    "Sophie": "Jonas",
    "Tim": "Sophie",
    "Jim": "Tim",
    "Marie": "Tim"
}
```

and the result will be 

```json
{
    "Jonas": [
        {
            "Sophie": [
                {
                    "Nick": [
                        {
                            "Pete": []
                        },
                        {
                            "Barbara": []
                        }
                    ]
                },
                {
                    "Tim": [
                        {
                            "Jim": []
                        },
                        {
                            "Marie": []
                        }
                    ]
                }
            ]
        }
    ]
}
```

if the input json is not valid, then it will return json with error - example
```json
{
    "error": "There is more than one top boss: Jonas, Jim, Marie"
}
```

### Upload employee json files
Go to index page: `/upload`: 
    1. Select the files in `test-files` folder - or your own files
    2. Select the way how the result show up    
        * `json`: the result will show up as the requirement
        * `chart`: the result will show up as the organization hierarchy chart.

## Notes
- For flexibility, we can specify null or empty string to indicate a employee as the top boss as below

```json
{ 
    "Lucas": "Jim",
    "Jim": "Tim",
    "Marie": "Tim",
    "Tim": ""
}
```

or 

```json
{ 
    "Lucas": "Jim",
    "Jim": "Tim",
    "Marie": "Tim",
    "Tim": "  "
}
```

or 

```json
{ 
    "Lucas": "Jim",
    "Jim": "Tim",
    "Marie": "Tim",
    "Tim": null
}
```
