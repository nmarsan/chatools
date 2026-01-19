FROM node:20-alpine

# Install bash and wget
RUN apk add --no-cache bash wget

# Set working directory to /app for build operations
WORKDIR /app

# Install dependencies
COPY package.json ./
RUN npm install

EXPOSE 3000

ENV PORT 3000
ENV HOSTNAME "0.0.0.0"

# Change to root directory and keep container running with logs to stdout
WORKDIR /
CMD ["bash", "-c", "echo 'Container started in /' && echo 'Project is in /app' && while true; do sleep 30; echo 'Container alive - $(date)'; done"]

