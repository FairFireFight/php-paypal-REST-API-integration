<h1>PainPal API Integration</h1>
<p>
  After 2 days, totaling 20 hours of agony, I've finally managed to decipher PayPal's API documention, and created an implementation of it in plain PHP.<br>
  I decided to release it publicly, as I wouldn't wish reading PayPal's Docs even on my worst enemies.<br>
  Do roast my mediocre code 👍
</p>
<hr>
<h1>Things To Note</h1>
<p>First of all, this is as raw as the implementation can get, the price is a fixed amount and isn't determined by the server, you'd ideally load it from a database. Second, you have to store information about each transaction in your DB, otherwise you will lose things like Order & Capture IDs (so you won't be able to refund orders from the API)</p>

<p>Small-ish things:</p>
<ul>
  <li>
    Your ClientID & Client Secret should be loaded from a 'secret' file, like .env or a secrets.json.
  </li>
  <li>
    The Authentication Token would ideally be cached somewhere, as it is valid for some 72-hours, but for low traffic sites it's fine as is.
  </li>
  <li>
    Again, this is just to show how the implementation of PayPal's API in code goes, this should never be used for anything other than an API example for PHP, which PayPal's docs fail to provide for anything other than Node.js (yuck)
  </li>
</ul>
