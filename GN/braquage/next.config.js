/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  output: 'standalone',
  // Include data directory in output tracing for standalone mode
  outputFileTracingIncludes: {
    '/*': ['./data/**/*'],
  },
  webpack: (config) => {
    config.resolve.alias.canvas = false;
    return config;
  },
}

module.exports = nextConfig

