package com.example.coursemanager;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;

import getRequest.SchoolDataService;

public class MainActivity extends AppCompatActivity {
    EditText host;
    EditText username;
    EditText password;
    Button loginbtn;
    CheckBox checkBox;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        this.host = findViewById(R.id.url);
        this.username = findViewById(R.id.username);
        this.password = findViewById(R.id.password);
        this.loginbtn= findViewById(R.id.loginbtn);
        this.checkBox= findViewById(R.id.checkBox);

        loginbtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                validateUser();
            }
        });

        checkBox.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                savePreferences();
            }
        });

        recoverPreferences();
}

    private void validateUser(){
        SchoolDataService schoolDataService=new SchoolDataService(MainActivity.this, this.host.getText().toString());
        schoolDataService.getResponse("students?uid="+this.password.getText().toString()+"?name="+this.username.getText().toString(), new SchoolDataService.VolleyResponseListener() {
            @Override
            public void onError(String message) {
                Toast.makeText(MainActivity.this, "The host is not correct", Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onResponse(JSONArray jsonObjectsArray) throws JSONException {


                if(username.getText().toString().equals(jsonObjectsArray.getJSONObject(0).getString("name"))){
                    Intent intent=new Intent(MainActivity.this,PrincipalActivity.class);
                    intent.putExtra("host", host.getText().toString());
                    intent.putExtra("username", username.getText().toString());
                    intent.putExtra("password", password.getText().toString());
                    startActivity(intent);

                    finish();
                }
                else{
                    Toast.makeText(MainActivity.this, "Not found in database", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    private void savePreferences(){
        SharedPreferences sharedPreferences=getSharedPreferences("loginPreferences", Context.MODE_PRIVATE);
        SharedPreferences.Editor editor=sharedPreferences.edit();
        editor.putString("username", this.username.getText().toString());
        editor.putString("password", this.password.getText().toString());
        editor.putString("host", this.host.getText().toString());
        editor.putBoolean("sesion", true);
        editor.commit();
    }

    private void recoverPreferences(){
        SharedPreferences sharedPreferences=getSharedPreferences("loginPreferences", Context.MODE_PRIVATE);
        this.username.setText(sharedPreferences.getString("username",""));
        this.password.setText(sharedPreferences.getString("password",""));
        this.host.setText(sharedPreferences.getString("host",""));

    }
}