#Descargar imagen nginx
FROM nginx:1.19

#Define y expone un argumento
ARG UID
EXPOSE $UID

#Añade un usuario en el contenedor, con el nombre appuser
RUN adduser -u ${UID} --disabled-password --gecos "" appuser

#Copia la configuración de default.conf a el fichero del contenedor
COPY default.conf /etc/nginx/conf.d/