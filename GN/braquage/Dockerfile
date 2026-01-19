FROM node:20-alpine

# Install bash and wget
RUN apk add --no-cache bash wget

# Set working directory to /data for build operations
WORKDIR /data

# Install dependencies (will be overridden by volume mount, but needed for initial setup)
COPY package.json ./
RUN npm install

EXPOSE 3000

ENV PORT 3000
ENV HOSTNAME "0.0.0.0"

# Change to root directory and keep container running with logs to stdout
WORKDIR /
CMD ["bash", "-c", "echo 'Container started in /' && echo 'Project is mounted in /data' && cd /data && npm run build 2>&1 || echo 'Build failed, container kept alive for debugging' && while true; do sleep 30; echo 'Container alive - $(date)'; done"]

