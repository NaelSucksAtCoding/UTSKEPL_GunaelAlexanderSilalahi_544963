# Tahap 1: build aplikasi Vue dengan Node.js
FROM node:22.12.0-alpine3.21 AS build

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

# Alamat API ditentukan saat build (bukan nilai rahasia).
ARG VITE_API_URL=http://localhost:8000/api
ENV VITE_API_URL=$VITE_API_URL

RUN npm run build

# Tahap 2: sajikan hasil build dengan Nginx.
# Hanya folder dist yang disalin, sehingga image akhir tidak berisi node_modules.
FROM nginx:1.27.3-alpine

COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=build /app/dist /usr/share/nginx/html

EXPOSE 80
