<!-- INSTALL GRAB68 ON NEW HOST -->


<!-- Preparation -->

Install Git + Composer if not available
Make Git repo public
Clone repo to host
Make Git repo private


<!-- Laravel config -->

Create .env file
Change Permission 0755 to storage and subfolders, bootstrap/cache folder
Run composer update


<!-- Setup Auto deploy -->

Chmod 0775 public/.gh/deploylive.xxx.sh
Chmod 0755 public/.gh/deploylive.xxx.php
Run test deploy webhook script https://grab68.sample/.gh/deploylive.php
Add webhook to Github repo
