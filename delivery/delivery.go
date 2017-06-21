package main

import "fmt"
import "github.com/go-redis/redis"
import "encoding/json"
import "net/http"
import "net/url"

type Delivery struct {
    Method string
    Url string
}

func main() {
	client := redis.NewClient(&redis.Options{
		Addr:     "localhost:31098",
		Password: "Isl5dAscpjt91rQFxtoGTVZtCn1P0K0ycXgRXPLs8ill30Sz36Dl0nOMWgJSqpYV",
		DB:       0,
	})
	
	pubsub := client.Subscribe("waitQueue")
	defer pubsub.Close()

	msg, err := pubsub.ReceiveMessage()
	if err != nil {
		panic(err)
	}
	
	var d Delivery
	errr := json.Unmarshal([]byte(msg.Payload), &d)
	if errr != nil {
		panic(err)
	}
	
	delivery(d.Method, d.Url)
	
	fmt.Println(d.Url, d.Method, msg.Payload)
}

func delivery(method string, target string) {
	if method == "GET" {
		http.Get(target)
	} else if method == "POST" {
		http.PostForm(target, url.Values{})
	} else {
		panic("Unsupported delivery method");
	}
}

