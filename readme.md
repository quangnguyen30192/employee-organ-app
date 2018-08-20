Setting up project on your machine

After you get the copy of this project. Run below scripts then it will work on your machine.
```bash
composer install
chmod -R o+rw storage
composer run post-root-package-install
composer run post-create-project-cmd

npm install
npm run dev
```

Go to index page: select and upload the files in `test-files` folder and enjoy!