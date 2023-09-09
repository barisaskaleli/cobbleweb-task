FROM nginx:latest as builder

FROM builder AS dev
COPY ./docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

FROM builder AS test

FROM builder AS prod
