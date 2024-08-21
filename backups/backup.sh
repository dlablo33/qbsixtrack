#!/bin/bash

# Configuración
HOST="viaduct.proxy.rlwy.net"
PORT="11013"
USER="root"
PASSWORD="ftMKHcANburGUFcaNNnVMJqnLQZRBvGo"
DBNAME="railway"
BACKUP_DIR="/backups"
DATE=$(date +'%Y-%m-%d_%H-%M-%S')
BACKUP_FILE="$BACKUP_DIR/$DBNAME-$DATE.sql"

# Crear respaldo
mysqldump -h $HOST -P $PORT -u $USER -p$PASSWORD $DBNAME > $BACKUP_FILE

# Opcional: Subir el respaldo a un almacenamiento en la nube (por ejemplo, AWS S3)
# aws s3 cp $BACKUP_FILE s3://mi-bucket/$DBNAME-$DATE.sql
