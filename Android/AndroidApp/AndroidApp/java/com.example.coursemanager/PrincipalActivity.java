package com.example.coursemanager;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TableLayout;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;

import getRequest.SchoolDataService;

public class PrincipalActivity extends AppCompatActivity {

    private String username;
    private String password;
    private String host;
    private final String timetable[] = {"day", "hour", "subject", "room"};
    private final String tasks[] = {"date", "subject", "name"};
    private final String marks[] = {"subject", "name", "mark"};
    private final String error[] = {""};

    Button logoutbtn;
    Button sendbtn;
    EditText queryBox;
    TextView usernameLabel;
    TextView tableTitle;
    private TableDynamic tableDynamic;
    TableLayout tableLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_principal);

        this.username = getIntent().getStringExtra("username");
        this.password = getIntent().getStringExtra("password");
        this.host = getIntent().getStringExtra("host");

        this.logoutbtn = findViewById(R.id.logoutbtn);
        this.sendbtn = findViewById(R.id.sendbtn);
        this.queryBox = findViewById(R.id.queryBox);
        this.tableLayout = findViewById(R.id.tableLayout);
        this.usernameLabel = findViewById(R.id.usernameLabel);
        this.usernameLabel.setText(username);
        this.tableTitle = findViewById(R.id.tableTitle);

        logoutbtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getApplicationContext(), MainActivity.class);
                startActivity(intent);
            }
        });
        sendbtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                dataRequest();
                hideKeybaord(view);
            }
        });
    }

    private void dataRequest() {
        SchoolDataService schoolDataService = new SchoolDataService(PrincipalActivity.this, this.host);

        String[] str = whichDatabase();
        String query;
        if(str[0].equals("subject")){//Cas particular de marks
            query=this.queryBox.getText().toString()+"&student_id="+this.password;
        }else{
            query=this.queryBox.getText().toString();
        }

        schoolDataService.getResponse(query, new SchoolDataService.VolleyResponseListener() {
            @Override
            public void onError(String message) {
                Toast.makeText(PrincipalActivity.this, "This query is not correct", Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onResponse(JSONArray jsonObjectsArray) throws JSONException {
                String[] header = whichDatabase();

                tableDynamic = new TableDynamic(tableLayout, PrincipalActivity.this, header, jsonObjectsArray);
                tableDynamic.createDataTable();
            }
        });
    }

    private String[] whichDatabase() {
        String s = this.queryBox.getText().toString();
        String[] title = s.split("\\?");
        String[] header = null;
        switch (title[0]) {
            case "timetable":
                header = timetable;
                this.tableTitle.setText("TIMETABLE");
                break;
            case "tasks":
                header = tasks;
                this.tableTitle.setText("TASKS");
                break;
            case "marks":
                header = marks;
                this.tableTitle.setText("MARKS");
                break;
            default:
                header=error;
                break;
        }
        return header;
    }

    private void hideKeybaord(View v) {
        InputMethodManager inputMethodManager = (InputMethodManager)getSystemService(INPUT_METHOD_SERVICE);
        inputMethodManager.hideSoftInputFromWindow(v.getApplicationWindowToken(),0);
    }
}