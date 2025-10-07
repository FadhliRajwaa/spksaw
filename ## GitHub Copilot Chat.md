## GitHub Copilot Chat

- Extension Version: 0.29.1 (prod)
- VS Code: vscode/1.102.3
- OS: Windows

## Network

User Settings:
```json
  "github.copilot.advanced.debug.useElectronFetcher": false,
  "github.copilot.advanced.debug.useNodeFetcher": true,
  "github.copilot.advanced.debug.useNodeFetchFetcher": true
```

Connecting to https://api.github.com:
- DNS ipv4 Lookup: 20.205.243.168 (6 ms)
- DNS ipv6 Lookup: Error (45 ms): getaddrinfo ENOTFOUND api.github.com
- Proxy URL: None (0 ms)
- Electron fetch (configured): HTTP 200 (365 ms)
- Node.js https: HTTP 200 (104 ms)
- Node.js fetch: HTTP 200 (3319 ms)

Connecting to https://api.individual.githubcopilot.com/_ping:
- DNS ipv4 Lookup: 140.82.114.22 (286 ms)
- DNS ipv6 Lookup: Error (46 ms): getaddrinfo ENOTFOUND api.individual.githubcopilot.com
- Proxy URL: None (28 ms)
- Electron fetch (configured): HTTP 200 (241 ms)
- Node.js https: HTTP 200 (765 ms)
- Node.js fetch: HTTP 200 (818 ms)

## Documentation

In corporate networks: [Troubleshooting firewall settings for GitHub Copilot](https://docs.github.com/en/copilot/troubleshooting-github-copilot/troubleshooting-firewall-settings-for-github-copilot).