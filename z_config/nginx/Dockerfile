FROM nginx:stable-alpine as base
WORKDIR /usr/share/nginx/html
ENTRYPOINT ["nginx", "-g", "daemon off;"]

#=======================================================================================================================
FROM base as prod
RUN rm -rf /usr/share/nginx/html/*
COPY z_config/nginx/nginx.conf /etc/nginx/nginx.conf
COPY frontend/dist/app /usr/share/nginx/html
COPY backend/public/bundles /usr/share/nginx/html/bundles

#=======================================================================================================================
FROM base as dev
