   ```
   php artisan tinker
   ```

2. **Run this code in the tinker prompt**:
   ```php
   App\Models\User::create([
       'name' => 'Admin',
       'email' => 'admin@example.com',
       'password' => bcrypt('password'), // change to secure password!
       'role' => 'admin'
   ]);
   ```
   - Change name, email, or password to your desired values.

3. **Exit Tinker** (press `Ctrl+C` or type `exit`).

4. You can now login as admin at `/login`.

---

## 2. Via Database Tool (e.g., SQLite Browser, TablePlus, etc.)

If using SQLite, MySQL, or PostgreSQL, you can directly insert a user with `role = 'admin'`:
- Open your `users` table.
- Insert a new row:
  - `name`: whatever you want
  - `email`: your email
  - `password`: _bcrypt hash of your password_ (generate via php or tinker)
  - `role`: `admin`
  - Example hash for password "password" is: `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`

---

## 3. Convert an existing teacher to admin (via Tinker)

If you already have a teacher and want to promote them:
```php
$user = App\Models\User::where('email', 'teacher@email.com')->first();
$user->role = 'admin';
$user->save();
```

---

**Tip:** Never allow open admin registration! Only register teachers or students via form.

Let me know if you want a GUI tool for admin creation, or if you want a CLI-only approach!