const path = require('path');

/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  output: 'standalone',
  webpack: (config) => {
    config.resolve.alias.canvas = false;
    // Ensure path aliases work correctly
    config.resolve.alias['@'] = path.resolve(__dirname);
    return config;
  },
}

module.exports = nextConfig

