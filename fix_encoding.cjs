const fs = require('fs');
const files = fs.readdirSync('resources/views/admin');
for (const file of files) {
  if (file.endsWith('.blade.php')) {
    let code = fs.readFileSync('resources/views/admin/' + file);
    let text = code.toString('utf8');
    text = text.split('\uFFFD').join(''); // Remove REPLACEMENT CHARACTER
    fs.writeFileSync('resources/views/admin/' + file, text, 'utf8');
    console.log('Cleaned ' + file);
  }
}
