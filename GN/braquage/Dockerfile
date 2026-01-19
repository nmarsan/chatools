# Development/Build stage - files will be mounted as volume
FROM node:20-alpine

WORKDIR /app

# Install dependencies (will be overridden by volume mount, but needed for build)
COPY package.json ./
RUN npm install

# The source code will be mounted as a volume in docker-compose
# So we just need to set up the environment

# Install wget for healthcheck
RUN apk add --no-cache wget

EXPOSE 3000

ENV PORT 3000
ENV HOSTNAME "0.0.0.0"

# Keep container running and allow manual build
CMD ["tail", "-f", "/dev/null"]

