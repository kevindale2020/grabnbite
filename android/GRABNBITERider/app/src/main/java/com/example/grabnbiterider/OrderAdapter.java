package com.example.grabnbiterider;

import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.List;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

public class OrderAdapter extends ArrayAdapter {
    public LayoutInflater inflater;
    public List<Order> orderList;
    public Context context;
    public double subtotal = 0;
    public double delivery_fee = 0;
    public double total = 0;
    public static final String ORDER_NO = "com.example.grabnbiterider.extra.ORDERNO";

    public OrderAdapter(List<Order> orderList, Context context) {
        super(context, R.layout.custom_layout, orderList);
        this.orderList = orderList;
        this.context = context;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {

        inflater = LayoutInflater.from(context);

        if (inflater == null) {
            inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if (convertView == null) {
            convertView = inflater.inflate(R.layout.custom_layout, null, true);
        }

        TextView tv_name = convertView.findViewById(R.id.tv_name);
        TextView tv_date = convertView.findViewById(R.id.tv_date);
        TextView tv_total = convertView.findViewById(R.id.tv_total);
        TextView tv_status = convertView.findViewById(R.id.tv_status);
        TextView tv_order_details = convertView.findViewById(R.id.tv_order_details);

        final Order order = orderList.get(position);

        tv_order_details.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getContext(), OrderDetailsActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                intent.putExtra(ORDER_NO, order.getId());
                getContext().startActivity(intent);
            }
        });

        subtotal = order.getTotal();
        delivery_fee = order.getFee();

        total = subtotal + delivery_fee;

        if(total < 0) {
            total = 0;
        }

        tv_name.setText(order.getName());
        tv_date.setText(order.getDate());
        tv_total.setText("₱" + String.format("%.2f", (total)));
        tv_status.setText(order.getStatus());

        return convertView;
    }
}
