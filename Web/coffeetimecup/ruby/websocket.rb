require 'rubygems'
require 'em-websocket'
require 'json'
require 'serialport'

sp = SerialPort.new('/dev/tty.usbmodemfd131', 9600, 8, 1, SerialPort::NONE)

EventMachine::WebSocket.start(:host => '127.0.0.1', :port => 8090) do |ws|
	ws.onopen    { ws.send "0" }
	ws.onclose   { puts "WebSocket closed" }

	ws.onmessage do
		message = sp.gets
		message.chomp!
		
		ws.send message
	end
end