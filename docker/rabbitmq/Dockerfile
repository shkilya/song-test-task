FROM rabbitmq:3-management

ADD docker/rabbitmq/rabbitmq.config /etc/rabbitmq/
ADD docker/rabbitmq/definitions.json /etc/rabbitmq/

RUN rabbitmq-plugins enable rabbitmq_shovel rabbitmq_shovel_management
