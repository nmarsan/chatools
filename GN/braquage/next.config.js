const path = require('path');

/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  output: 'standalone',
  webpack: (config) => {
    config.resolve.alias.canvas = false;
    
    // Explicitly resolve @ alias to project root
    config.resolve.alias['@'] = path.resolve(__dirname);
    
    // Ensure TypeScript files are resolved
    config.resolve.extensions = [
      '.tsx',
      '.ts',
      '.jsx',
      '.js',
      '.json',
      ...config.resolve.extensions,
    ];
    
    return config;
  },
}

module.exports = nextConfig

