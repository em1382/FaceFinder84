# Author: Ellis Madagan
# Last update: 2/25/2018

from http.server import BaseHTTPRequestHandler
import socketserver
import facecut


class HTTPRequestHandler(BaseHTTPRequestHandler):

    def do_POST(self):
        content_length = int(self.headers['Content-Length'])
        post_data = self.rfile.read(content_length)

        self.send_response(200)
        self.send_header('Content-Type', 'text/html')
        self.end_headers()

        payload = post_data.decode('utf-8').split()[-2]
        facecut.process_image(payload)


def run():
    # Set up and start server
    httpd = socketserver.TCPServer(('', 1337), HTTPRequestHandler)
    httpd.serve_forever()

run()
