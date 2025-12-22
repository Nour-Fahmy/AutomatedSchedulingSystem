# Google Calendar Integration Setup Guide

## What is the Google Calendar Credentials File?

The `google_credentials.json` file is a JSON configuration file downloaded from Google Cloud Console. It contains your OAuth 2.0 client credentials that allow your application to authenticate with Google Calendar API.

## Step-by-Step Setup Instructions

### 1. Go to Google Cloud Console
Visit: https://console.cloud.google.com/

### 2. Create or Select a Project
- Click the project dropdown at the top
- Click "New Project" (or select an existing project)
- Enter project name: "AlignUp Calendar Integration"
- Click "Create"

### 3. Enable Google Calendar API
- In the left sidebar, go to **APIs & Services** → **Library**
- Search for "Google Calendar API"
- Click on "Google Calendar API"
- Click the **Enable** button

### 4. Create OAuth 2.0 Credentials
- Go to **APIs & Services** → **Credentials**
- Click **Create Credentials** → **OAuth client ID**
- If prompted, configure the OAuth consent screen first:
  - User Type: **External** (for testing) or **Internal** (if using Google Workspace)
  - App name: "AlignUp"
  - User support email: Your email
  - Developer contact: Your email
  - Click **Save and Continue** through the steps
  - Click **Back to Dashboard**

### 5. Create OAuth Client ID
- Application type: **Web application**
- Name: "AlignUp Calendar Integration"
- Authorized redirect URIs:
  ```
  http://localhost:8000/google/callback
  ```
  (Add this exact URL - for production, also add your production URL)
- Click **Create**

### 6. Download Credentials
- After creating, you'll see a popup with your Client ID and Client Secret
- Click **Download JSON** button (or copy the JSON)
- The downloaded file will be named something like `client_secret_xxxxx.json`

### 7. Save the File in Your Project
- Rename the downloaded file to: `google_credentials.json`
- Move it to: `storage/app/google_credentials.json`
- Make sure the file structure matches the example format:

```json
{
  "web": {
    "client_id": "your-client-id.apps.googleusercontent.com",
    "project_id": "your-project-id",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_secret": "your-client-secret",
    "redirect_uris": [
      "http://localhost:8000/google/callback"
    ]
  }
}
```

### 8. Verify the File Location
The file should be at:
```
storage/app/google_credentials.json
```

## Security Notes

⚠️ **Important**: 
- Never commit `google_credentials.json` to version control (Git)
- Add it to `.gitignore` 
- Keep your `client_secret` confidential
- For production, use environment variables or secure credential storage

## Testing

After setting up the credentials file:
1. Go to your Settings page: `http://localhost:8000/settings`
2. Click "Connect Google Calendar" button
3. You'll be redirected to Google to authorize the application
4. After authorization, you'll be redirected back and see "Google Calendar connected successfully!"

## Troubleshooting

**Error: "Credentials file not found"**
- Verify the file exists at `storage/app/google_credentials.json`
- Check file permissions (should be readable by your web server)
- Verify the JSON format is valid

**Error: "Redirect URI mismatch"**
- Make sure the redirect URI in Google Cloud Console exactly matches: `http://localhost:8000/google/callback`
- For production, add your production URL as well

**Error: "Access blocked"**
- If using External user type, you may need to add test users in OAuth consent screen
- Go to OAuth consent screen → Test users → Add your email address

