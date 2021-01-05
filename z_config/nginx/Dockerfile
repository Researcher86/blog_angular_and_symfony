FROM node:15.5.0-alpine3.12 as frontend
RUN mkdir /app
WORKDIR /app

COPY frontend /app
RUN yarn install && npm run build

#=======================================================================================================================
FROM nginx:stable-alpine as base
WORKDIR /usr/share/nginx/html
ENTRYPOINT ["nginx", "-g", "daemon off;"]

#=======================================================================================================================
FROM base as prod
RUN rm -rf /usr/share/nginx/html/*
COPY z_config/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --from=frontend /app/dist/app /usr/share/nginx/html

#=======================================================================================================================
FROM base as dev
