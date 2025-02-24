jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      # Add permissions step before npm operations
      - name: Set permissions
        run: |
          mkdir -p /home/***/htdocs/pro.sdssn.org/sdssnapp/node_modules || true
          # /home/sdssn-pro/htdocs/pro.sdssn.org/sdssnapp/public
          sudo chmod -R 777 /home/***/htdocs/pro.sdssn.org/sdssnapp
        
      # Rest of your build steps
      - name: Install dependencies
        run: npm install
        
      - name: Install Vite globally
        run: npm install -g vite
        
      - name: Build
        run: npm run build






jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      # Add this SSH step with proper permissions
      - name: SSH and set permissions
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            chmod -R 775 /home/sdssn-api/htdocs/api.sdssn.org/sdssnapp
            chown -R sdssn-api:sdssn-api /home/sdssn-api/htdocs/api.sdssn.org/sdssnapp
      
      # Then continue with your build steps
      - name: Install dependencies
        run: npm install
        
      - name: Build
        run: npm run build        