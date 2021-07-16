# Use the docker backend for profile
sed -i 's/127.0.0.1:402/profile_web:80/' /etc/haproxy/haproxy_backend.cfg
systemctl reload haproxy
