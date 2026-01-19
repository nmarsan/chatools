FROM node:20-alpine

# Install bash and wget
RUN apk add --no-cache bash wget

WORKDIR /app

# Install dependencies
COPY package.json ./
RUN npm install

EXPOSE 3000

ENV PORT 3000
ENV HOSTNAME "0.0.0.0"

# Keep container running in root directory instead of /app
CMD ["bash", "-c", "cd / && tail -f /dev/null"]

