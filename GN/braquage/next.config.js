const path = require('path');

/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  output: 'standalone',
  // Include data directory in output tracing
  outputFileTracingIncludes: {
    '/*': ['./data/**/*'],
  },
  webpack: (config) => {
    config.resolve.alias.canvas = false;
    
    // Explicitly configure path aliases for webpack
    config.resolve.alias['@'] = path.resolve(__dirname);
    
    return config;
  },
}

module.exports = nextConfig

