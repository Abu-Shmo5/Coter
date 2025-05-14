CURRENT_FILE_PATH="${BASH_SOURCE[0]}"
CURRENT_FILE_PATH=$(dirname -- $CURRENT_FILE_PATH)
cd "$CURRENT_FILE_PATH/api"
sudo rsync -av -u . /var/www/coter/api/
cd "../web/"
ng build --configuration development
# ng build
cd "dist/web/"
sudo rsync -av -u . /var/www/coter/web/
cd "../../../public/nginx"
sudo rsync -av -u local.conf /etc/nginx/sites-available/coter