FROM node:20-alpine

WORKDIR /app

# Install dependencies
COPY package.json ./
RUN npm install

# Install wget for healthcheck
RUN apk add --no-cache wget

EXPOSE 3000

ENV PORT 3000
ENV HOSTNAME "0.0.0.0"

# Keep container running for manual debugging
# Files will be mounted as volumes from docker-compose
CMD ["tail", "-f", "/dev/null"]

