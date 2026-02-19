# Railway Environment Variables - Quick Setup Guide

## Critical Variables (MUST ADD - These fix your error)

Add these 4 variables FIRST in Railway dashboard:

```
APP_KEY = base64:6NQgLaINcA7KADaG2Y0v83EYAHhMJjzkazkTN+J7NDI=
APP_ENV = production
APP_DEBUG = false
APP_URL = https://barangaydaangbakalsystem-production.up.railway.app
```

## Steps to Add Variables to Railway:

1. Go to https://railway.app
2. Select your project: **barangaydaangbakalsystem**
3. Click on your service/app
4. Go to the **Variables** tab
5. Click **+ Add Variable**
6. For each variable above:
   - Enter the variable name (e.g., `APP_KEY`)
   - Enter the value (e.g., `base64:6NQgLaINcA7KADaG2Y0v83EYAHhMJjzkazkTN+J7NDI=`)
   - Click **Add**
7. Once ALL variables are added, click **Deploy** button to redeploy your app

## Complete Variable List (All recommended):

See `.env.production.example` file for the complete list of all recommended variables.

## What This Fixes:

- ❌ **Before**: `Illuminate\Encryption\MissingAppKeyException - No application encryption key has been specified`
- ✅ **After**: Your app will load successfully with proper encryption enabled

## Important Notes:

- DO NOT include quotes around values
- The `APP_KEY` must start with `base64:`
- After adding variables, Railway will automatically trigger a redeploy
- Wait 2-3 minutes for deployment to complete
- Check your app at https://barangaydaangbakalsystem-production.up.railway.app

## If You Need a New Key:

Run locally:
```bash
php artisan key:generate --show
```

Copy the output and use it as your `APP_KEY` value in Railway variables.
