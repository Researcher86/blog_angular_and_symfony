FROM centrifugo/centrifugo:v2.8 as base

#=======================================================================================================================
FROM base as dev
COPY config.json /centrifugo/config.json

#=======================================================================================================================
FROM base as prod
COPY config.json /centrifugo/config.json