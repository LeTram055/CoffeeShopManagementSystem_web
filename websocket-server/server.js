require("dotenv").config();
const express = require("express");
const http = require("http");
const socketIo = require("socket.io");
const Redis = require("ioredis");

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

const redis = new Redis({
    host: "127.0.0.1",
    port: 6379
});

redis.subscribe("laravel_database_orders", function (err, count) {
    console.log("Subscribed to laravel_database_orders channel");
});


redis.on("connect", function () {
    console.log("Redis connected successfully!");
});
redis.on("error", function (error) {
    console.error("Redis connection error:", error);
});

redis.on("message", (channel, message) => {
    console.log(`Message from channel ${channel}:`, message);

    if (channel === "laravel_database_orders") {
        try {
            const parsedMessage = JSON.parse(message);
            io.emit("order.created", parsedMessage);
        } catch (error) {
            console.error("Error JSON from Redis:", error);
        }
    }
});



io.on("connection", (socket) => {
    console.log("A user connected");

    socket.on("new-message", (message) => {
        console.log("Received message:", message);
        io.emit("new-message", { text: message });
    });

    socket.on("disconnect", () => {
        console.log("User disconnected");
    });
});


server.listen(3000, () => {
    console.log("WebSocket server listening on port 3000");
});
