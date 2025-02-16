To configure a GitHub SSH key for a VPS server, follow these steps:

1. **Generate an SSH Key**: On your VPS, use the command:
   ```bash
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
   ```
   Press enter to accept the default file location, and set a passphrase if desired.

2. **Add the SSH Key to the SSH Agent**:
   ```bash
   eval "$(ssh-agent -s)"
   ssh-add ~/.ssh/id_rsa
   ```

3. **Copy the SSH Key to GitHub**: Use the command to output the SSH key:
   ```bash
   cat ~/.ssh/id_rsa.pub
   ```
   Then, log in to your GitHub account, navigate to **Settings > SSH and GPG keys**, and click **New SSH key**. Paste the copied SSH key here.

4. **Test the SSH Connection**:
   ```bash
   ssh -T git@github.com
   ```
   If configured correctly, you will see a success message.

Make sure to double-check all steps and key entries.