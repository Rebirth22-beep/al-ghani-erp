# ============================================================
# Frontend dev image — Vite dev server with HMR.
# Used by docker-compose.yml for local development.
# ============================================================
FROM node:20-alpine

WORKDIR /app

# Install deps separately so Docker can cache them
COPY package.json package-lock.json* ./
RUN npm ci

# Source is mounted as a volume in compose, so we don't COPY everything
EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host"]
