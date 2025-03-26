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

redis.subscribe("laravel_database_orderevent", function (err, count) {
    console.log("Subscribed to laravel_database_orderevent channel");
});

redis.subscribe("laravel_database_ordercompleted", function (err, count) {
    console.log("Subscribed to laravel_database_ordercompleted channel");
});

redis.subscribe("laravel_database_lowstock", function (err, count) {
    console.log("Subscribed to laravel_database_lowstock channel");
});


redis.on("connect", function () {
    console.log("Redis connected successfully!");
});
redis.on("error", function (error) {
    console.error("Redis connection error:", error);
});

redis.on("message", (channel, message) => {
    console.log(`Message from channel ${channel}:`, message);

    if (channel === "laravel_database_orderevent") {
        try {
            const parsedMessage = JSON.parse(message);
            io.emit("order.event", parsedMessage);
            console.log(`✅ Đã gửi event order.event tới client`);
        

        } catch (error) {
            console.error("Error JSON from Redis:", error);
        }
    }

    if (channel === "laravel_database_ordercompleted") {
        try {
            const parsedMessage = JSON.parse(message);
            io.emit("order.completed", parsedMessage);
            console.log(`✅ Đã gửi event order.completed tới client`);
        } catch (error) {
            console.error("Error JSON from Redis:", error);
        }
    }

    if (channel === "laravel_database_lowstock") {
        try {
            const parsedMessage = JSON.parse(message);
            io.emit("lowstock.event", parsedMessage);
            console.log(`✅ Đã gửi event lowstock.event tới client`);
        

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
